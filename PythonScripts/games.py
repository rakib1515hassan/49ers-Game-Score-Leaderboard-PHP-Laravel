import requests
from faker import Faker
import random
import time
from login import JWT_Access_Token_Get

fake = Faker()

# Define your API endpoint
api_endpoint = "http://192.168.10.107:8001/api/games"

jwt_access_token = JWT_Access_Token_Get()
print("JWT Token =", jwt_access_token)




# Function to generate fake game data
def generate_fake_game_data(team1_id, team2_id):
    return {
        'date': fake.date(),
        'location': fake.city(),  # Random city as location
        'score': f"{random.randint(0, 10)} - {random.randint(0, 10)}",  # Random score
        'result': random.choice(['Win', 'Loss', 'Draw']),  # Random result
        'team1_id': team1_id,
        'team2_id': team2_id,
        'win_team_id': random.choice([team1_id, team2_id])  # Randomly choose between team1_id and team2_id
    }



def upload_game_data(game_data):
    headers = {'Authorization': f'Bearer {jwt_access_token}'}
    
    # Show loading message and start countdown
    print("Sending data to the server...")
    countdown = 5  # Duration of the countdown in seconds
    while countdown > 0:
        print(f"Loading... {countdown} seconds remaining", end='\r')  # Use \r to overwrite the line
        time.sleep(1)  # Wait for 1 second
        countdown -= 1

    # Send the request
    response = requests.post(api_endpoint, json=game_data, headers=headers)

    print("\nResponse Status Code:", response.status_code)
    print("Response Text:", response.text)  # Print the raw response text

    if response.status_code == 201:
        print("Game created successfully:", response.json())
    else:
        print("Failed to create game:", response.text)




# Get user input for team IDs
team1_id = input("Enter the ID for Team 1: ")
team2_id = input("Enter the ID for Team 2: ")

# Change 10 to however many games you want to create
num_games = 10  # Set how many games you want to create
for _ in range(num_games):  
    game_data = generate_fake_game_data(team1_id, team2_id)
    upload_game_data(game_data)
