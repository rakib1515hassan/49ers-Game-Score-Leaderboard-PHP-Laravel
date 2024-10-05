import requests
from faker import Faker
import random
from login import JWT_Access_Token_Get

fake = Faker()

# Define API endpoints
teams_api_endpoint   = "http://192.168.10.107:8001/api/teams"
players_api_endpoint = "http://192.168.10.107:8001/api/players"
games_api_endpoint   = "http://192.168.10.107:8001/api/games"

# Get JWT access token
jwt_access_token = JWT_Access_Token_Get()
headers = {'Authorization': f'Bearer {jwt_access_token}'}

# Function to generate fake team data
def generate_fake_team_data():
    return {
        'name': fake.company(),
        'title': fake.catch_phrase(),
        'logo': None  # Replace with actual logo if necessary
    }

# Function to generate fake player data
def generate_fake_player_data(team_id):
    return {
        'f_name': fake.first_name(),
        'l_name': fake.last_name(),
        'email': fake.email(),
        'position': random.choice(['Forward', 'Guard', 'Center']),
        'height': f"{random.randint(6, 7)}'{random.randint(0, 11)}\"",
        'weight': random.randint(180, 250),
        'age': random.randint(20, 35),
        'experience': random.randint(1, 15),
        'college': fake.company(),
        'avatar': None,  # You can add real avatar if necessary
        'team_id': team_id
    }

# Function to generate fake game data
def generate_fake_game_data(team1_id, team2_id):
    return {
        'date': fake.date(),
        'location': fake.city(),
        'score': f"{random.randint(0, 100)} - {random.randint(0, 100)}",
        'result': random.choice(['Win', 'Loss', 'Draw']),
        'team1_id': team1_id,
        'team2_id': team2_id,
        'win_team_id': random.choice([team1_id, team2_id])
    }

# Function to upload data to API
def upload_data(endpoint, data):
    response = requests.post(endpoint, json=data, headers=headers)
    print(f"\nSending data to {endpoint}...\nResponse Status Code:", response.status_code)
    print("Response Text:", response.text)
    if response.status_code == 201:
        print("Data created successfully:", response.json())
        return response.json()
    else:
        print("Failed to create data:", response.text)
        return None

# Step 1: Insert 10 teams
team_ids = []
print("Inserting 10 teams...\n")
for _ in range(10):
    team_data = generate_fake_team_data()
    team_response = upload_data(teams_api_endpoint, team_data)
    if team_response:
        team_ids.append(team_response['id'])  # Collect team IDs

    # Step 2: Insert 5 Players for each team
    print(f"Inserting 5 players for Team ID {team_response['id']}...\n")
    for _ in range(5):
        player_data = generate_fake_player_data(team_response['id'])
        upload_data(players_api_endpoint, player_data)

# Step 3: Insert 10 games between the teams
print("\nInserting 10 games between the teams...\n")
for _ in range(10):
    # Select two random teams for the game, ensuring team1_id != team2_id
    team1_id, team2_id = random.sample(team_ids, 2)
    game_data = generate_fake_game_data(team1_id, team2_id)
    upload_data(games_api_endpoint, game_data)

print("\nScript execution complete.")
