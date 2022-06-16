import mysql.connector

cnx = None

try:
    # DBに接続
    cnx = mysql.connector.connect(
        host='db',
        port='3306',
        user='gaia',
        password='ZjDwXLxZcCr5fmSk6u',
        database='gaia_db'
    )

    cursor = cnx.cursor()

    sql = "SELECT  bukken_num, update_date, change_date FROM lands"

    cursor.execute(sql)
    rows = cursor.fetchall()
 
    cursor.close()

except Exception as e:
    print(f"Error Occurred: {e}")

finally:
    if cnx is not None and cnx.is_connected():
        cnx.close()

# print(rows)
# property_result = "300115518317" in rows
# update_result = "令和 4年 4月25日" in rows
# change_result = "令和 5年 3月25日" in rows

# 物件番号があるか調べる
for row in rows:
    property_result = '300117877040' in row
    # print(str(row) + ":" + str(property_result))
    if property_result:
        bukken_data = row
        break
    else:
        bukken_data = None

if bukken_data:
    update_result = "令和 4年 4月25日" in bukken_data[1]
    change_result = "令和 4年 5月25日" in bukken_data[2]
else:
    update_result = False
    change_result = False
# print(rows)
# print(bukken_data[0])
print(update_result)
print(change_result)
# print('300115518317' in rows.values())

# print(property_result)
# print(update_result)
# print(change_result)

