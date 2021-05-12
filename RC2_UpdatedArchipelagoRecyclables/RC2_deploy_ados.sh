#!/bin/bash

# Part of the Archipelago Commons Project
# This is to be used as companion of the https://github.com/esmero/archipelago-deployment/ deployment strategy
# Works starting on 8.x-1.0-beta3  
# Assuming you cloned this repository (archipelago-recyclables) inside the d8content/ folder of your local clone of 
# https://github.com/esmero/archipelago-deployment/
# Via docker:
# This should be run as: docker exec -ti esmero-php bash -c 'd8content/archipelago-recyclables/deploy_ados.sh'
# Standalone installation (no docker). Simply d8content/archipelago-recyclables/deploy_ado

echo -ne "Deploying Set of Demo Objects to your Archipelago, please wait until this is done before having a coffee\n"
echo -ne "Running as " && whoami;
# Means Drush can read the site!
drush status bootstrap | grep -q Successful;
(($? == 0)) && { 
drush archipelago:jsonapi-ingest /var/www/html/d8content/archipelago-recyclables/ado/2189d20f-bcac-43d2-96e8-a1ef37a25860.json --uuid=2189d20f-bcac-43d2-96e8-a1ef37a25860 --bundle=digital_object --uri=http://esmero-web --files=/var/www/html/d8content/archipelago-recyclables/ado/2189d20f-bcac-43d2-96e8-a1ef37a25860 --user=jsonapi --password=jsonapi --moderation_state=published;
drush archipelago:jsonapi-ingest /var/www/html/d8content/archipelago-recyclables/ado/17355bdb-d784-4037-96fe-5c160296e639.json --uuid=17355bdb-d784-4037-96fe-5c160296e639 --bundle=digital_object --uri=http://esmero-web --files=/var/www/html/d8content/archipelago-recyclables/ado/17355bdb-d784-4037-96fe-5c160296e639 --user=jsonapi --password=jsonapi --moderation_state=published;
drush archipelago:jsonapi-ingest /var/www/html/d8content/archipelago-recyclables/ado/902f6fc9-4fbd-4d1e-9efd-43de0db564af.json --uuid=902f6fc9-4fbd-4d1e-9efd-43de0db564af --bundle=digital_object --uri=http://esmero-web --files=/var/www/html/d8content/archipelago-recyclables/ado/902f6fc9-4fbd-4d1e-9efd-43de0db564af --user=jsonapi --password=jsonapi --moderation_state=published;
drush archipelago:jsonapi-ingest /var/www/html/d8content/archipelago-recyclables/ado/e9401cbe-9b1d-4b13-ab92-a56f1ff426fa.json --uuid=e9401cbe-9b1d-4b13-ab92-a56f1ff426fa --bundle=digital_object --uri=http://esmero-web --files=/var/www/html/d8content/archipelago-recyclables/ado/e9401cbe-9b1d-4b13-ab92-a56f1ff426fa --user=jsonapi --password=jsonapi --moderation_state=published;
drush archipelago:jsonapi-ingest /var/www/html/d8content/archipelago-recyclables/ado/9eb28775-d73a-4904-bc79-f0e925075bc5.json --uuid=9eb28775-d73a-4904-bc79-f0e925075bc5 --bundle=digital_object --uri=http://esmero-web --files=/var/www/html/d8content/archipelago-recyclables/ado/9eb28775-d73a-4904-bc79-f0e925075bc5 --user=jsonapi --password=jsonapi --moderation_state=published;
drush archipelago:jsonapi-ingest /var/www/html/d8content/archipelago-recyclables/ado/f4a4c6ee-4ce9-4b4c-8704-e8057bad0a7d.json --uuid=f4a4c6ee-4ce9-4b4c-8704-e8057bad0a7d --bundle=digital_object --uri=http://esmero-web --files=/var/www/html/d8content/archipelago-recyclables/ado/f4a4c6ee-4ce9-4b4c-8704-e8057bad0a7d --user=jsonapi --password=jsonapi --moderation_state=published;
drush archipelago:jsonapi-ingest /var/www/html/d8content/archipelago-recyclables/ado/f4fe3bfa-f0bf-4c55-8af7-f90f9ace092d.json --uuid=f4fe3bfa-f0bf-4c55-8af7-f90f9ace092d --bundle=digital_object --uri=http://esmero-web --files=/var/www/html/d8content/archipelago-recyclables/ado/f4fe3bfa-f0bf-4c55-8af7-f90f9ace092d --user=jsonapi --password=jsonapi --moderation_state=published;
drush archipelago:jsonapi-ingest /var/www/html/d8content/archipelago-recyclables/ado/75bc5d07-3239-4313-addf-b095dd2a7903.json --uuid=75bc5d07-3239-4313-addf-b095dd2a7903 --bundle=digital_object --uri=http://esmero-web --files=/var/www/html/d8content/archipelago-recyclables/ado/75bc5d07-3239-4313-addf-b095dd2a7903 --user=jsonapi --password=jsonapi --moderation_state=published;
drush archipelago:jsonapi-ingest /var/www/html/d8content/archipelago-recyclables/ado/e42ab9c9-9cfd-4b18-86ae-696e0361298b.json --uuid=e42ab9c9-9cfd-4b18-86ae-696e0361298b --bundle=digital_object --uri=http://esmero-web --files=/var/www/html/d8content/archipelago-recyclables/ado/e42ab9c9-9cfd-4b18-86ae-696e0361298b --user=jsonapi --password=jsonapi --moderation_state=published;
drush archipelago:jsonapi-ingest /var/www/html/d8content/archipelago-recyclables/ado/fd2eadf8-e718-4867-b1f7-9c70f07da5f2.json --uuid=fd2eadf8-e718-4867-b1f7-9c70f07da5f2 --bundle=digital_object --uri=http://esmero-web --files=/var/www/html/d8content/archipelago-recyclables/ado/fd2eadf8-e718-4867-b1f7-9c70f07da5f2 --user=jsonapi --password=jsonapi --moderation_state=published;
drush archipelago:jsonapi-ingest /var/www/html/d8content/archipelago-recyclables/ado/a008ad6c-04de-4df0-9b3b-a0c9e8359e2e.json --uuid=a008ad6c-04de-4df0-9b3b-a0c9e8359e2e --bundle=digital_object --uri=http://esmero-web --files=/var/www/html/d8content/archipelago-recyclables/ado/a008ad6c-04de-4df0-9b3b-a0c9e8359e2e --user=jsonapi --password=jsonapi --moderation_state=published;
drush archipelago:jsonapi-ingest /var/www/html/d8content/archipelago-recyclables/ado/25233e88-6ec4-4467-93f6-cb2ac10e8914.json --uuid=25233e88-6ec4-4467-93f6-cb2ac10e8914 --bundle=digital_object --uri=http://esmero-web --files=/var/www/html/d8content/archipelago-recyclables/ado/25233e88-6ec4-4467-93f6-cb2ac10e8914 --user=jsonapi --password=jsonapi --moderation_state=published;
drush archipelago:jsonapi-ingest /var/www/html/d8content/archipelago-recyclables/ado/16f210dd-90b2-4ef2-8343-4d60ac7defb4.json --uuid=16f210dd-90b2-4ef2-8343-4d60ac7defb4 --bundle=digital_object_collection --uri=http://esmero-web --user=jsonapi --password=jsonapi --moderation_state=published;

drush cr; 
}

echo -e "If you see no errors, you are done Ingesting your first set of Demo Objects. Enjoy! (and have that coffee)"