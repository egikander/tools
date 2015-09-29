# Install & configure *memcache* (Ubuntu)

### 1. Installation server *"memcached"*

**Install:**  
`sudo apt-get install memcached`

**Check config:**

Path to config:  
`/etc/memcached.conf`

**Content:**

``` apacheconf
#Memcached run as a demon
-d
#Path to log file
logfile /var/log/memcached.log
#Storage memory size 256Mb
-m 256
#Listen port
-p 11211
#Run from user
-u memcache
#Listen host
-l 127.0.0.1
```

**Start/Stop:**

`sudo service memcached start/stop`

**Check for running:**

`sudo netstat -tap | grep memcached`

**Output example:**

```
tcp     0   0 localhost:11211    *:*    LISTEN      31534/memcached
```

### 2. Installation module *"memcache"* for PHP

**Install:**  

```
sudo apt-get install php-pear
sudo apt-get build-essential
sudo pecl install memcache
```

### 3. Add module settings to config files for *Apache*:  

Path to config:  
`/etc/php5/apache2/php.ini`

``` apacheconf
[memcache]
memcache.hash_strategy = "consistent"
memcache.max_failover_attemps = 100
memcache.allow_failover = 1
```

Add ini file:  

`echo 'extension=memcache.so' | sudo tee /etc/php5/apache2/conf.d/memcache.ini`

### 4. Add module settings to config files for *CLI*:  

Path to config:  
`/etc/php5/cli/php.ini`

```
[memcache]
memcache.hash_strategy = "consistent"
memcache.max_failover_attemps = 100
memcache.allow_failover = 1
```


Add ini file:  

`echo 'extension=memcache.so' | sudo tee /etc/php5/cli/conf.d/memcache.ini`



### 5. Check settings:

`php -i | grep -i memcache`

Output example:  

```
/etc/php5/cli/conf.d/memcache.ini
memcache
memcache support => enabled
memcache.allow_failover => 1 => 1
memcache.chunk_size => 8192 => 8192
memcache.default_port => 11211 => 11211
memcache.default_timeout_ms => 1000 => 1000
memcache.hash_function => crc32 => crc32
memcache.hash_strategy => consistent => consistent
memcache.max_failover_attempts => 20 => 20
```
