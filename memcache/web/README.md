# MemcacheTool web app

This is simple web application for monitoring your **Memcache** keys and values. Need to have installed **PHP** with **Memcache** support. Not for *production* use, use this tool on your own sandbox for debugging. :-)

# Installation

1. `git clone https://github.com/egikander/tools.git`
2. Go to `memcache/web` directory
3. Run `composer install`
4. Run `npm install`
5. Run `bower install`

Now you can start the app server use util script `./serverstart` in the *root* of *web* directory, or 
start it with *npm* - `npm start`.

After start app will be available by address `http://localhost:8080`

You can change **Memcache** server address and port in *serverstart* util script. Also you can change WS app server address and port in `src/config.js`, and there you can configure data refresh time, parameter - `refresh_time`.
