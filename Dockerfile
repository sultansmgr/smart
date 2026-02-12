FROM php:8.2-apache
# Proje dosyalarını sunucuya kopyala
COPY . /var/www/html/
# Port ayarını Render'a göre dinamik yap
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
# Apache'yi başlat
CMD ["apache2-foreground"]
