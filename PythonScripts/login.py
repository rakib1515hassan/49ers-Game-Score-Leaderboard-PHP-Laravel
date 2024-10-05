import requests

def JWT_Access_Token_Get():
    api_endpoint = "http://192.168.10.107:8001/api/auth/login"

    email = "admin@gmail.com"
    password = "12345678"

    response = requests.post(api_endpoint, json={"email": email, "password": password})

    if response.status_code == 200:
        print("Login successful!")
        # print("Access Token = ", response.json()['access_token'])
        return response.json()['access_token']
    else:
        print("Login failed!")
        print("Error = ", response.json()['error'])
        return response.json()['error']



# JWT_Access_Token_Get()