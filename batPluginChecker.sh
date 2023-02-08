#!/bin/bash

#  $$$$$$$\           $$\                              $$\                               $$\                  $$\                         $$\                        $$\             
#  $$  __$$\          $$ |                             $$ |                              $$ |                 \__|                        $$ |                       $$ |            
#  $$ |  $$ |$$$$$$\$$$$$$\   $$$$$$$\$$$$$$\  $$$$$$$\$$$$$$$\  $$$$$$\         $$$$$$\ $$ $$\   $$\ $$$$$$\ $$\$$$$$$$\         $$$$$$$\$$$$$$$\  $$$$$$\  $$$$$$$\$$ |  $$\       
#  $$$$$$$\ |\____$$\_$$  _| $$  _____\____$$\$$  _____$$  __$$\$$  __$$\       $$  __$$\$$ $$ |  $$ $$  __$$\$$ $$  __$$\       $$  _____$$  __$$\$$  __$$\$$  _____$$ | $$  |      
#  $$  __$$\ $$$$$$$ |$$ |   $$ /     $$$$$$$ $$ /     $$ |  $$ $$$$$$$$ |      $$ /  $$ $$ $$ |  $$ $$ /  $$ $$ $$ |  $$ |      $$ /     $$ |  $$ $$$$$$$$ $$ /     $$$$$$  /       
#  $$ |  $$ $$  __$$ |$$ |$$\$$ |    $$  __$$ $$ |     $$ |  $$ $$   ____|      $$ |  $$ $$ $$ |  $$ $$ |  $$ $$ $$ |  $$ |      $$ |     $$ |  $$ $$   ____$$ |     $$  _$$<        
#  $$$$$$$  \$$$$$$$ |\$$$$  \$$$$$$$\$$$$$$$ \$$$$$$$\$$ |  $$ \$$$$$$$\       $$$$$$$  $$ \$$$$$$  \$$$$$$$ $$ $$ |  $$ |      \$$$$$$$\$$ |  $$ \$$$$$$$\\$$$$$$$\$$ | \$$\       
#  \_______/ \_______| \____/ \_______\_______|\_______\__|  \__|\_______|      $$  ____/\__|\______/ \____$$ \__\__|  \__|       \_______\__|  \__|\_______|\_______\__|  \__|      
#  $$\                      $$$$$$$$\               $$\                         $$ |                 $$\   $$ |                                                                      
#  $$ |                     \__$$  __|              $$ |                        $$ |                 \$$$$$$  |                                                                      
#  $$$$$$$\ $$\   $$\          $$ |$$$$$$\  $$$$$$\ $$$$$$$\  $$$$$$\           \__|                  \______/                                                                       
#  $$  __$$\$$ |  $$ |         $$ |\____$$\$$  __$$\$$  __$$\$$  __$$\                                                                                                               
#  $$ |  $$ $$ |  $$ |         $$ |$$$$$$$ $$ |  \__$$ |  $$ $$$$$$$$ |    
#  $$ |  $$ $$ |  $$ |         $$ $$  __$$ $$ |     $$ |  $$ $$   ____|    
#  $$$$$$$  \$$$$$$$ |         $$ \$$$$$$$ $$ |     $$ |  $$ \$$$$$$$\      
#  \_______/ \____$$ |         \__|\_______\__|     \__|  \__|\_______|  
#           $$\   $$ |                                                                                                                                                               
#           \$$$$$$  |                                                                                                                                                               
#            \______/  
      

#Culprit Plugin Scanner for Batcache 
#This bash script is used to find the culprit plugin that is breaking the batcache on a Wordpress website. T
#he steps involved are as follows:

#1 - Get the site URL using wp option get siteurl command.
#2 - Check the batcache status by searching for the "batcache" string in the site's HTML using curl command.
#3 - If batcache is not active on the site, the script will prompt the user to confirm if they want to continue with the scan.
#4 - If the user confirms to continue, the script will backup the database using wp db export command.
#5 - Deactivate all active plugins one by one and check if batcache becomes active after each deactivation.
#6 - If a plugin is found to be breaking the batcache, the script will output the name of the plugin and exit.
#7 - If no plugin is found to be breaking the batcache, the script will output a message indicating that no plugin was found                                        

#This bash script is used to find the culprit plugin that is breaking the batcache on Pressable site.

#Get the site URL
site_url=$(wp option get siteurl)

#Check if batcache is active
batcache_status=$(curl -s "$site_url" | grep "batcache")

if [ -z "$batcache_status" ]; then
echo -e "\033[31mBatcache is N/A on this site.\033[0m"
echo -n "Do you want to continue scanning for the culprit plugin (y/n)? "
read answer
if [ "$answer" = "y" ]; then
# Backup the database
wp db export

echo -e "\033[32mPlease wait, I'll find the plugin breaking the cache on this site.\033[0m"

# Deactivate all plugins one by one and check if batcache becomes active
for plugin in $(wp plugin list --status=active --field=name); do
  echo "Scanning for culprit plugin..."
  wp plugin deactivate $plugin
  batcache_status=$(curl -s "$site_url" | grep "batcache")
  if [ ! -z "$batcache_status" ]; then
    echo -e "The plugin \033[31m$plugin\033[0m is breaking batcache on the site."
    exit 0
  fi
  wp plugin activate $plugin
done

echo -e "\033[32mNo plugin was found to be breaking batcache on the site.\033[0m"

fi
else
echo -e "\033[32mBatcache is active on this site.\033[0m"
echo -n "Do you want to continue scanning for the culprit plugin (y/n)? "
read answer
if [ "$answer" = "n" ]; then
exit 0
fi
fi
