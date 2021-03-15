FROM ubuntu:bionic
RUN 	apt-get update && apt-get -y install wget gnupg2 && \
	wget -qO - https://artifacts.elastic.co/GPG-KEY-elasticsearch | apt-key add - && \
	echo "deb https://artifacts.elastic.co/packages/7.x/apt stable main" | tee -a /etc/apt/sources.list.d/elastic-7.x.list && \
	apt-get update && \
	apt-get -y dist-upgrade && \
	DEBIAN_FRONTEND="noninteractive" apt-get install -y imagemagick \
			   tesseract-ocr \
			   openjdk-8-jre \
			   apt-transport-https \
			   tesseract-ocr-eng \
			   tesseract-ocr-por \
			   xdotool \
			   x11-apps \
			   php7.2-cli \
			   php7.2-curl \
			   php7.2-gd \
			   elasticsearch \
			   curl && \
	ln -fs /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime && \
	dpkg-reconfigure --frontend noninteractive tzdata 
RUN groupadd -g 1000 capture && useradd -u 1000 -g 1000 -d /capture capture
RUN sed -i /etc/elasticsearch/elasticsearch.yml \
	-e 's/#cluster.name: my-application/cluster.name: LifeLog/g' \
	-e 's/#network.host: 192.168.0.1/network.host: 0.0.0.0/g'  \
	-e 's/#discovery.seed_hosts: \["host1", "host2"\]/discovery.seed_hosts: \["127.0.0.1"\]/' && \
	echo "discovery.type: single-node" >> /etc/elasticsearch/elasticsearch.yml
ADD capture /usr/bin/capture
ADD startup /startup
ADD files.tar /capture
RUN chown capture:capture /etc/default/elasticsearch && \
	chown -R capture:capture /usr/share/elasticsearch && \
	chown -R capture:capture /etc/elasticsearch && \
	chown -R capture:capture /var/lib/elasticsearch && \
	chown -R capture:capture /var/log/elasticsearch  && \
	chmod +x /capture/scheduler /capture/indexer /startup /usr/bin/capture

ENTRYPOINT "/startup"
