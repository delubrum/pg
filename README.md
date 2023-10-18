REQUERIMIENTOS DEL SISTEMA:

    • Servidor Nginx o Apache
    • Php 8 con extensiones (mbstring, gd , xml, zip)
    • Mysql con timezones cargadas (mysql_tzinfo_to_sql)
    • composer
    • git

En resumen un servidor LAMP

INSTALACIÓN SIGMA:

    • Crear una base de datos  con nombre sigma y restaurar la ultima copia de seguridad del sistema desde mysql.
    • Clonar el repositorio de sigma desde github
    • Copiar el archivo .env.example remover  .example y configurar los datos como se piden dentro del archivo.
    • Ejecutar composer install
    • En mysql ejecutar set global sql_mode='';
