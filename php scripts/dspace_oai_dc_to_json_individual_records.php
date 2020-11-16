<?PHP
// This depends on a call like this
// docker run -ti esmero/catmandu bash -c "catmandu convert OAI --url https://dome.mit.edu/oai/request --metadataPrefix oai_dc --set col_1721.3_74804 --handler struct" > dim_connick.json
$arguments = getopt("i:o:");

// -i is path our JSON file
// -o is the output path for files (base one)
// php dspace_oai_pdf.php -i "connick_coll_job_files.json" -o "mitJSONfiles/"

$srcunix = preg_replace('/\s/i', '\ ',$arguments['i']);
$destunix = preg_replace('/\s/i', '\ ',$arguments['o']);
$fileurl = 'http://dome.mit.edu/bitstream/';

$json = file_get_contents($srcunix);
$oaistream = json_decode($json,true);
$numberofrecords = 0;
foreach($oaistream as $record) {
  if (isset($record["identifier"])) {
    $url = NULL;
    foreach($record["identifier"] as $key => $id) {
      // This is weird but that is how Dspace builds this
      // http://hdl.handle.net/
      if ($key == 0) {
        $itemid = $id;
      }
    }
	$record["dspace_type"] = $record["type"]; 
	$record["type"] = "Photograph";
	$record["label"] = $record["title"][0]; 
	unset($record["_resumption"]);
	$recordinjson = json_encode($record);
	file_put_contents($destunix . "/" . $itemid . ".json", $recordinjson);
	if ($numberofrecords == 2000){
		break;
	}
  }
}
