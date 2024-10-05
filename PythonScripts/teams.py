import requests
from faker import Faker
import random
import time
from login import JWT_Access_Token_Get

fake = Faker()

# Define your API endpoint
api_endpoint = "http://192.168.10.107:8001/api/teams"

jwt_access_token = JWT_Access_Token_Get()
print("JWT Token =", jwt_access_token)

# Function to generate fake team data
def generate_fake_team_data():
    return {
        'name': fake.company(),
        'title': fake.catch_phrase(),
        'logo': None  
    }

def upload_team_data(team_data):
    headers = {'Authorization': f'Bearer {jwt_access_token}'}
    
    print("Sending data to the server...")
    countdown = 5  
    while countdown > 0:
        print(f"Loading... {countdown} seconds remaining", end='\r')  
        time.sleep(1)  # Wait for 1 second
        countdown -= 1

    # Send the request
    response = requests.post(api_endpoint, json=team_data, headers=headers)

    print("\nResponse Status Code:", response.status_code)
    print("Response Text:", response.text)  

    # if response.status_code == 201:
    #     print("Team created successfully:", response.json())
    # else:
    #     print("Failed to create team:", response.text)




# Change 10 to however many teams you want to create
for _ in range(10):  
    team_data = generate_fake_team_data()
    upload_team_data(team_data)
