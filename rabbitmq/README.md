# Scripts for monitoring RabbitMQ queues (*nix)

 **rabbitstatus** - bash script updates every 2 seconds and displays the following information:

 - Queue name
 - Number of processing messages
 - Number of messages ready for processing
 - Total number of messages
 - Durability of queue
 - The queue is temporary or not
 - Number of consumers

*Usage:*

`sudo rabbitstatus`

*Output:*

```
Every 2,0s: sudo /usr/sbin/rabbitmqctl list_queues name messages_unacknowledged messages_ready messages durable auto_delete consumers | grep ...  Mon Sep 21 18:23:50 2015

hello      0  0  0  false  true   0
queue      0  0  0  true   false  0
rpc_queue  0  1  1  false  false  0
```

 **rabbitinfo** - *PHP CLI* script(requires php), displays the same information like **rabbitstatus**.

*Usage:*
```
sudo rabbitinfo -t <timeout> [-h]

     -t <timeout> - delay in seconds for updating data
     -h - help
```

*Output:*

```
Queue name|Processing messages|Ready messages|Total messages|Queue durability|Temporary|Consumers
--------------------------------------------------------------------------------------------------
  hello     0                   0              0               false           true       0
  queue     0                   0              0               true            false      0
  rpc_queue 0                   1              1               false            false     0

```
