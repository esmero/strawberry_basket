<?PHP
// This depends on a call like this
// docker run -ti esmero/catmandu bash -c "catmandu convert OAI --url https://dome.mit.edu/oai/request --metadataPrefix dim --set col_1721.3_74804 --handler struct" > dim_connick.json
$arguments = getopt("i:o:");

// -i is path our JSON file
// -o is the output path for files (base one)
// php dspace_oai_dim_to_json_individual_records.php -i "dim_connick.json" -o "mitJSONfiles/"
$idprefix = "oai:dome.mit.edu:1721.3/";
$idprefix_lenght = strlen($idprefix);
$srcunix = preg_replace('/\s/i', '\ ',$arguments['i']);
$destunix = preg_replace('/\s/i', '\ ',$arguments['o']);
$fileurl = 'http://dome.mit.edu/bitstream/';
$numberofrecords = 0; // don't change
$json = file_get_contents($srcunix);
$oaistream = json_decode($json, true);
$maxrecords = 0; // Set to the number you want to process or 0 for all
$newrecord = [];
$cache = [];

// Setup reconciliation (your server)
$archipelago_server_base = "http://localhost:8001";

$reconciliate['subject'] = [
  [
    'source' => 'loc',
    'vocab' => 'subjects',
    'type' => 'thing',
    'key' => 'subject_loc'
  ],
  [
    'source' => 'aat',
    'vocab' => 'exact',
    'type' => 'thing',
    'key' =>  'subject_aat_getty'
  ],
];

$reconciliate['technique'] = [
  [
    'source' => 'aat',
    'vocab' => 'exact',
    'type' => 'thing',
    'key' => 'technique_aat_getty'
  ]
];
// https://dome.archipelago.nyc/webform_strawberry/auth_autocomplete/loc/rdftype/personalName/10/?q=Diego
// 'source' => 'loc', 'vocab'=>'rdftype', 'type'=>'personalName' key is where you want to save this.

$reconciliate['worktype'] = [
  [
    'source' => 'aat',
    'vocab' => 'exact',
    'type' => 'thing',
    'key' => 'worktype_aat_getty'
  ]
];

$reconciliate['format_medium'] = [
  [
    'source' => 'aat',
    'vocab' => 'exact',
    'type' => 'thing',
    'key' => 'format_medium_aat_getty'
  ]
];

// All in this set share these
$common_record_values = [
  "type" => "Photograph",
  "ismemberof" => 5,
  "warcs" => [],
  "audios" => [],
  "images"=> [],
  "models"=> [],
  "videos"=> [],
  "documents" => [],
  "ap:entitymapping" => [
    "entity:file" => [
      "images",
      "warcs",
      "documents",
      "audios",
      "videos",
      "models"
    ],
    "entity:node" => [
      "ismemberof"
    ]
  ],
  "strawberry_field_widget_id" => "dc_descriptive_metadata"
];


// Select only one ID
// Select range

foreach($oaistream as $record) {
  $numberofrecords++;
  if (isset($record["_identifier"])) {
    $newrecord = [];
    $itemid = substr($record["_identifier"], $idprefix_lenght, strlen($record["_identifier"]) - $idprefix_lenght);
    $newrecord['local_id'] = $itemid;
    if (isset($record["_metadata"])) {
      //metadata is in $record[0]["_metadata"][2]
      foreach ($record["_metadata"][2] as $metadata) {
        $qualifier = isset($metadata[1]["qualifier"]) ? '_' . $metadata[1]["qualifier"] : '';
        $field_name = strtolower($metadata[1]["element"]) . $qualifier;
        if (isset($newrecord[$field_name])) {
          if (!is_array($newrecord[$field_name])) {
            $tempvalue = $newrecord[$field_name];
            $newrecord[$field_name] = [];
            $newrecord[$field_name][] = $tempvalue;
            $newrecord[$field_name][] =  preg_replace('/\s+/', ' ',$metadata[2][0]);
          }
          else {
            $newrecord[$field_name][] = preg_replace('/\s+/', ' ',$metadata[2][0]);
          }
        }
        else {
          $newrecord[$field_name] = preg_replace('/\s+/', ' ',$metadata[2][0]);
        }
      }

      // let's get contributors and creators.
      if (isset($newrecord['creator'])) {
        foreach((array)$newrecord['creator'] as $creator) {
          $reconoutput = reconciliate($archipelago_server_base, $creator, 'loc', 'rdftype', 'PersonalName', $cache);
          if ($reconoutput) {
            $newrecord['creator_personal_name_loc'][] = $reconoutput;
          }
        }
      }
      if (isset($newrecord['contributor'])) {
        foreach((array)$newrecord['contributor'] as $contributor) {
          $reconoutput = reconciliate($archipelago_server_base, $contributor, 'loc', 'rdftype', 'PersonalName', $cache);
          if ($reconoutput) {
            $newrecord['contributor_personal_name_loc'][] = $reconoutput;
          }
        }
      }

      foreach($newrecord as $key => $values) {
        if (isset($reconciliate[$key])) {
          foreach((array) $values as $value) {
            foreach ($reconciliate[$key] as $reconciliate_item) {
              $reconoutput = reconciliate($archipelago_server_base, $value, $reconciliate_item['source'], $reconciliate_item['vocab'],$reconciliate_item['type'], $cache);
              if ($reconoutput) {
                $newrecord[$reconciliate_item['key']][] = $reconoutput;
              }
            }
          }
        }
      }
      // Reassign some values for Allison's webform
      $newrecord['label'] = $newrecord['title'];
      $newrecord['date_digital'] = $newrecord['date_available'];

      $recordinjson = json_encode($newrecord + $common_record_values);
      $itemname = $newrecord['identifier'];
      file_put_contents($destunix . "/" . $itemname . ".json", $recordinjson);
    }


    if ($numberofrecords == $maxrecords) {
      file_put_contents( "allreconciliated.json",  json_encode($cache));
      break;
    }
  }
}
// WIKI DATA returns a full label and a description. To get the pure label we need to remove the description from the label.

function reconciliate($serverbase ,$value, $source = 'loc', $vocab = 'subjects', $type = 'thing', &$cache) {
  $value = trim($value);
  $first_one = NULL;
  $endpoint = $serverbase."/webform_strawberry/auth_autocomplete/{$source}/{$vocab}/{$type}/10/?q=".urlencode($value);
  if (isset($cache[$endpoint])) {
    return $cache[$endpoint];
  }
  $serviceresponse = json_decode(file_get_contents($endpoint),FALSE);
  if (!empty($serviceresponse)) {
    foreach($serviceresponse as $response) {
      if (trim($response->label) == $value) {
        $first_one = ['label' => $response->label, 'uri' => $response->value];
        break;
      }//check first for complete match
    }
    if (empty($first_one)) {
      $first_one = reset($serviceresponse);
      if (!isset($first_one->value) || empty($first_one->value)) {
        $first_one = NULL;
      } else {
        // Only case where we may have a 'desc'
        if (isset($first_one->desc))  {
          $label = trim(substr($first_one->label, 0, strlen($first_one->label) - strlen($first_one->desc)));
        }
        else
        {
          $label = trim($first_one->label);
        }
        // Only case where we may have a 'desc'
        $first_one = ['label' => $label, 'uri' => $first_one->value];
      }

    }
  }
  else {
    // Means we got no response, we assume there is no match.
    $first_one = NULL;
  }
  $cache[$endpoint] = $first_one;

  return $first_one;
}

