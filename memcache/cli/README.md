# memcachetool 0.0.1

Tool for monitoring your **Memcache** keys and values. Need to have installed **PHP** with **Memcache** support. Not for *production* use, use this tool on your own sandbox for debugging. :-)

## Installation:

1. `git clone https://github.com/egikander/tools.git`
2. Go to `memcache/cli` directory
3. Run `composer install`

Now you can use *memcachetool*.

## Basic commands:

1. `memcachetool show` - Shows all your **Memcache** keys and values in table.  
    1.1. `memcachetool show -a localhost -p 11211` - Defines server address and port(default **localhost:11221**).  
    1.2. `memcachetool show --count` - Shows number of the keys in storage.  
    1.3. `memcachetool show --number 2` - Number of keys that will be shown(for example 2 items).  
    1.4. `memcachetool show key1..keyN` - Names of keys that will be shown.  
    1.5. `memcachetool show --help` - Shows help.  
2. `memcachetool flush` - Flushes all keys or only selected keys.  
    2.1. `memcachetool flush -a localhost -p 11211` - Defines server address and port(default **localhost:11211**).  
    2.2. `memcachetool flush` - Flushes **ALL** keys and values.  
    2.3. `memcachetool flush key1..keyN` - Names of the keys that will be flushed.  
    2.4. `memcachetool flush --help` - Shows help.  
