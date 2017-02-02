#Docker image hosting botsarena

FROM tutum/lamp:latest

MAINTAINER Gnieark https://blog-du-grouik.tinad.fr


RUN apt-get update && \
    apt-get install -q -y php5-curl
    
#delete symbolic linc  
RUN rm /var/www/html && \
    mkdir /var/www/html
    
#Copy bots arena code
COPY html/ /var/www/html/
COPY src/ /var/www/src/
COPY lang /var/www/lang/

#Some config and mysql scripts
ADD dockerConfig/populate_mysql.sh /populate_mysql.sh

RUN cp /var/www/src/config.php.empty /var/www/src/config.php && \
    chmod +x /populate_mysql.sh && \
    chown -R www-data:www-data /var/www
    
EXPOSE 80

ADD install.sql /install.sql
RUN rm /run.sh
ADD dockerConfig/run.sh /run.sh
RUN chmod +x /run.sh

CMD ["/run.sh"]