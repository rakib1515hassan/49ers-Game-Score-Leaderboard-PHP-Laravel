import requests
from faker import Faker
import random
import time
from login import JWT_Access_Token_Get

fake = Faker()

# Define your API endpoint
api_endpoint = "http://192.168.10.107:8001/api/players"  

jwt_access_token = JWT_Access_Token_Get()
print("JWT Token =", jwt_access_token)



# Function to generate fake player data
def generate_fake_player_data(team_id):
    return {
        'f_name': fake.first_name(),
        'l_name': fake.last_name(),
        'email': fake.unique.email(),                # Ensure email is unique
        'position': random.choice(['Forward', 'Midfielder', 'Defender', 'Goalkeeper']),
        'height': f"{random.randint(160, 210)} cm",  # Random height in cm
        'weight': random.randint(60, 100),           # Random weight in kg
        'age': random.randint(18, 40),               # Random age
        'experience': random.randint(0, 20),         # Random experience in years
        'college': fake.company(),                   # Fake college name
        'team_id': team_id                           # Assign the team ID
    }

def upload_player_data(player_data):
    headers = {'Authorization': f'Bearer {jwt_access_token}'}
    
    # Show loading message and start countdown
    print("Sending data to the server...")
    countdown = 5  # Duration of the countdown in seconds
    while countdown > 0:
        print(f"Loading... {countdown} seconds remaining", end='\r')  
        time.sleep(1)  # Wait for 1 second
        countdown -= 1

    # Send the request
    response = requests.post(api_endpoint, json=player_data, headers=headers)

    print("\nResponse Status Code:", response.status_code)
    print("Response Text:", response.text)  # Print the raw response text

    if response.status_code == 201:
        print("Player created successfully:", response.json())
    else:
        print("Failed to create player:", response.text)




# Change 10 to however many players you want to create
team_id = 2  # Change this to the appropriate team ID you want to assign players to
for _ in range(10):  
    player_data = generate_fake_player_data(team_id)
    upload_player_data(player_data)
