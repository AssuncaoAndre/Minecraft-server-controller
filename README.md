## Disclaimer
This PHP file makes use of several bash processes and can be considered unsafe. Use at your own risk and only allow trusted people to access this dashboard.

This code was written way before ChatGPT was created. Any bad code is really of my own mind. 

## Why I took on this work
I play minecraft with my friends and I self-host the Minecraft server. However, I frequently go to sleep before they do so there was a real need for them to execute basic control actions like starting and stopping servers and deleting worlds (we play on an hardcore mode where once somene dies we delete the world).

## How does it work
* Each Minecraft server has a ServerStart.sh file. There is one example in this repo
* The command to start the server uses a custom flag -DmineServer=<server_name>. This allows me to grep the running processes and figure out the PID of the server and its status (running or not running).

## Running instructions
* Make sure each server has a ServerStart.sh file
* php -S localhost:8000

## Future improvements (I probably won't ever work on this)
* Detect before page load which servers are running and which ones are stopped.
* Find a way to automatically identify for each server if the world is deletable or not without hardcoding it in the server.

## Contributing
Feel free to open a PR if you want to make any improvement 

<img width="1829" height="569" alt="image" src="https://github.com/user-attachments/assets/2b769ff2-944b-4606-bfa2-96c5a881ab09" />
