FROM virtualvinodh/avalokitam

ADD ./spa /var/www/
ADD ./*.php /var/www/
ADD ./*.txt /var/www/

RUN sed -i 's#AllowOverride [Nn]one#AllowOverride All#' /etc/apache2/apache2.conf

RUN sed -i 's/80/8080/' /etc/apache2/ports.conf
