#!/bin/bash
# this needs to be in the same folder as your .json files.
# inside that folder there needs to be also a files folder and a ingested folder
# json files need to be named the same as the folders with files that are inside the files folder.
# e.g /persistent/drupal8/d8content/ingest1/files/172220/some.tif and the json file would be /persistent/drupal8/d8content/ingest1/172220.json
# You call as:
# docker exec -ti esmero-php bash -c "d8content/ingest1/ingest_some.sh" > $HOME/ingest_log_2020-12-08.log 
j=0
process=1
password=PUTYOUROWNPASSWORD
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
for i in $DIR/*.json; do
  f="$(basename -- $i)"

  let "j+=1"
  echo "row $j"
  echo "${f%%.*}"
  if [[ $process -eq 1 ]]; then
    drush archipelago:jsonapi-ingest $i  --bundle=digital_object --uri=https://dome.archipelago.nyc  --files=$DIR/files/${f%%.*} --user=jsonapi --password=$password --moderation_state=published;
    mv $i $DIR/ingested/${f%%.*}.json
  fi
done
