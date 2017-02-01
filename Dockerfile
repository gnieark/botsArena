FROM tutum/lamp:latest
#delete symbolic linc
RUN rm /var/www/html && \
    mkdir /var/www/html

COPY html/ /var/www/html/
COPY src/ /var/www/src/
COPY lang /var/www/lang/

ADD dockerConfig/populate_mysql.sh /populate_mysql.sh

RUN cp /var/www/src/config.php.empty /var/www/src/config.php && \
    chmod +x /populate_mysql.sh && \
    chown -R www-data:www-data /var/www
    
EXPOSE 80 3306
ADD install.sql /install.sql
RUN rm /run.sh
ADD dockerConfig/run.sh /run.sh
RUN chmod +x /run.sh



CMD ["/run.sh"]