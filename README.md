# Ecomuseo LLAQTA AMARU -YOYEN KUWA

![t2i](public/images/welcome/fachada_ecomuseo.jpg)

# Como subir la web a un servidor

Ingresa por ssh con el programa putty usando los accesos necesarios



Ingresar a la siguiente ruta con el comando cd

~~~
cd /var/www/
~~~

clonar el repositorio que quieres probrar

~~~
git clone https://github.com/And3rs14/ecomuseo.git
~~~

Si el repositorio ya está, elimina la carpeta con el siguiente comando
~~~
rm -rf ecomuseo/*
~~~

COmprobar la eliminacion entrando al link en tu navegador
~~~
Ingresa al enlace de la web
~~~

Ahora si puedes clonar sin problema
~~~
git clone https://github.com/And3rs14/ecomuseo.git /tmp/ecomuseo-temp
~~~

~~~
cp -r /tmp/ecomuseo-temp/* /var/www/ecomuseo/ && rm -rf /tmp/ecomuseo-temp
~~~

Una vez clonado entre en el proyecto
~~~
cd ecomuseo
~~~

Instala composer, poner yes
~~~
composer install
~~~

Dar permisos a estas carpetas
~~~
sudo chown -R www-data:www-data /var/www/ecomuseo/storage && sudo chown -R www-data:www-data /var/www/ecomuseo/bootstrap/cache
~~~
	Si te pide contraseña, poner la del usuario

ejecuta el comando para crear la base de datos y seedearlas
~~~
php artisan migrate:fresh --seed
~~~

genera el build
~~~
npm run build
~~~

Y ya estaría! 😁❤️
