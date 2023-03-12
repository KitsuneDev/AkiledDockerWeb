import questionary
import mariadb
from dotenv import dotenv_values
import os
import hashlib
from time import sleep
def hashed(password: str):
    return hashlib.sha1(hashlib.md5(hashlib.md5(password.encode('utf-8')).hexdigest().encode('utf-8')).hexdigest().encode('utf-8')).hexdigest()
def CreateUser(config):
    if config is None:
        if os.path.isfile(".env"):
            config = dotenv_values(".env")
        else:
            print("ERROR: Cannot infer config - Running from wrong folder")
            return

    print("Waiting for Data Plaform startup...")
    TOTAL_RETRIES = 25
    for i in range(0, TOTAL_RETRIES):
        try:
            conn = mariadb.connect(user="root", password="", host="127.0.0.1", port=int(config["MARIADB_HOST_PORT"]),
                                   database="mezz")
            break
        except mariadb.Error as e:
            sleep(10)
            print(f"Data Platform: {e}")
            print(f"{TOTAL_RETRIES-i} retries left.")
            continue
    username = questionary.text("Enter the new Username").ask()
    password = questionary.password("Enter the new Password").ask()
    email = questionary.text("Enter the new Email").ask()
    pin = ""
    while len(pin) != 4:
        pin = questionary.text("Enter the new Admin Pin (4-digit)").ask()
    cursor = conn.cursor()
    cursor.execute("""
    INSERT INTO users (username, password, mail, rank, motto, account_created, last_online, look, gender, credits, home_room, pin) 
    VALUES ( ?, ?, ?,'25','Admin User', NOW(), NOW(), '','M','100000',86, ?);""",
                   (username, hashed(password), email, pin))
    conn.commit()
    conn.close()
    print("Done.")


if __name__ == "__main__":
    CreateUser(None)
