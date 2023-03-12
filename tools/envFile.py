import os
import questionary
def EnvFile():
    if os.path.isfile('.env'):
        if questionary.confirm("A Global Config Already Exists - Would you like to reset it?").ask():
            os.remove('.env')
        else:
            return False
    if not os.path.isfile('.env.template'):
        print("ERROR: You are probably running this from the wrong directory.")
        return False
    templateFile = open('.env.template', 'r')
    templateLines = templateFile.readlines()
    templateFile.close()
    configLines = []
    for line in templateLines:
        if not all(x in line for x in ["#", "="]):
            continue
        key = line.split('=')[0]
        value = line.split('=')[1].split("#")[0].strip()
        comment = line.split('#')[1].strip()
        print("------")
        print(f" Configuring: {key}")
        print(f" Example Value: {value}")
        print(f" Notes: {comment}")
        newVal = ""
        while newVal == "":
            newVal = questionary.text(f"Enter the new value for {key}: ").ask()
        configLines.append(f"{key}={newVal}")
    print("Saving Config...")
    with open(".env", 'w') as f:
        for line in configLines:
            f.write(f"{line}\n")


if __name__ == "__main__":
    EnvFile()