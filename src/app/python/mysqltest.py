import mysql.connector

# connect mysql
cnx = mysql.connector.connect(
    host='172.20.0.2',
    port='3306',
    user='root',
    password='SbXT2arKSJKFnNiNku',
    database='gaia_db'
)
cursor_ = cnx.cursor()

# check table
query3 = "SHOW TABLES"
cursor_.execute(query3)
print(cursor_.fetchall())
