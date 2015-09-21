# Scripts for monitoring RabbitMQ queues (*nix)

1. *rabbitstatus* - bash script updates every 2 seconds and displays the following information:

- [1] Queue name
- [2] Number of processing messages
- [3] Number of messages ready for processing
- [4] Total number of messages
- [5] Durability of queue
- [6] The queue is temporary or not
- [7] Number of consumers

*Usage:*

`sudo rabbitstatus`

*Output:*

```
Every 2,0s: sudo /usr/sbin/rabbitmqctl list_queues name messages_unacknowledged messages_ready messages durable auto_delete consumers | grep ...  Mon Sep 21 18:23:50 2015

hello      0  0  0  false  true   0
queue      0  0  0  true   false  0
rpc_queue  0  1  1  false  false  0
```

2. *rabbitinfo* - PHP CLI script(requires php), displays the same information like *rabbitstatus*.

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
