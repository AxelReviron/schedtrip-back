<p align="center">
  <img src="/resources/assets/schedtrip-logo.png" alt="SchedTrip logo" width="412" />
  <br>
  <img src="https://img.shields.io/badge/status-work%20in%20progress-yellow" alt="Status badge" />
  <img src="https://img.shields.io/badge/license-MIT-green" alt="License badge" />
  <br>
  <img src="https://img.shields.io/badge/Laravel-12.x-red?logo=laravel&logoColor=red" alt=""/>
  <img src="https://img.shields.io/badge/Vue-3.x-41B883?logo=vue.js&logoColor=green" alt=""/>
  <img src="https://img.shields.io/badge/Inertia-2.x-blueviolet" alt=""/>
  <img src="https://img.shields.io/badge/Docker-Compose-blue?logo=docker" alt=""/>
  <img src="https://img.shields.io/badge/MySQL-8.x-white?logo=mysql&logoColor=white" alt=""/>
</p>

## 🌴 SchedTrip

**SchedTrip** is an open-source trip planning app built with Laravel and Vue 3 (via Inertia).  
It allows you to organize solo or group adventures using interactive maps, personalized notes, and packing lists.


## ✨ Features

- Create **public or private trips**
- Invite friends to **view or edit your trips**
- Add **stops** via a **search bar** or **interactive map**
- Automatic **route calculation**
- Set **arrival and departure dates** for each stop 
- Add **notes** to each stop
- **Add / Remove / Block** friends


## 🗒️ TODO
### v1
- [x] Ability to switch languages (currently English-only)
- [ ] Handle 429 (Rate Limiting)
- [ ] Settings Page (change user info, add OpenRouteService API key)
- [ ] Refactor API calls into dedicated services
- [ ] Use composables for UI or component-related logic
- [ ] Centralize store management (avoid direct state changes in pages/components)
- [ ] Privacy Policy Page
- [ ] Terms Of Service Page
- [ ] About Page
- [ ] Packing list management (items + quantity)
- [ ] Refactor locale file structure
- [ ] Use MySQL container for CI and enable code coverage

### v2
- [ ] Frontend tests
- [ ] Add more languages
- [ ] Real-time collaboration and notifications (Websockets)
- [ ] Media support (images for trips and users)

## 🤝 Contributing
SchedTrip is a personal project, while it’s not yet open to external contributions, I welcome feedback and bug reports to improve it.

If you find a bug or want to submit a feature idea, feel free to open an [issue](https://github.com/AxelReviron/schedtrip/issues).

> Contributions will be opened once the project reaches a more stable version.


## 💻 Installation

#### 1. Clone the repository
```bash
git clone https://github.com/AxelReviron/schedtrip.git
cd schedtrip
```

#### 2. Copy the environment file
```bash
cp .env.example .env
```

#### 3. Update .env variables (for the db)
see `compose.dev.yaml` for required vars.

#### 4. Start Docker containers
```bash
docker-compose -f compose.dev.yaml up -d
```

#### 5. Run migrations
```bash
docker exec app php artisan migrate
```
#### 6. (Optional) Seed sample data
```bash
docker exec app php artisan db:seed
```

## 💚 Credits & Thanks

SchedTrip wouldn't be possible without the following great projects :

- [Leaflet](https://leafletjs.com) — for the interactive maps and marker system
- [OpenRouteService](https://openrouteservice.org/) — for route calculations and geolocation search
