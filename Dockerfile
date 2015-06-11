FROM wordpress:4.2-fpm
MAINTAINER Linus Wallin version: 0.1

# Update and install
RUN apt-get -y update
RUN apt-get install -y git ansible vim supervisor

# Clone git repos
COPY docker/ansible /tmp/ansible-script

# Run ansible configuration
RUN cd /tmp/ansible-script && ansible-playbook site.yml -c local

# Copy over wp-content
COPY ./wp-content /var/www/html/wp-content/

# Copy over custom run script an supervisord script
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/run.sh /run.sh
RUN chmod 755 /run.sh

ENTRYPOINT ["/usr/bin/supervisord"]
