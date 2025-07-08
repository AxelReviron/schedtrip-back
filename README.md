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

---

## ✨ Features

- Create **public or private trips**
- Invite friends to **view or edit your trips**
- Add **stops** via a **search bar** or **interactive map**
- Automatic **route calculation**
- Set **arrival and departure dates** for each stop 
- Add **notes** to each stop
- **Add / Remove / Block** friends

---

## 🗒️ TODO

- [ ] User settings page (change info, add OpenRouteService API key)
- [ ] Packing list management (items + quantity)
- [ ] Real-time collaboration and notifications
- [ ] More languages + ability to switch (currently English-only)

---

## 🤝 Contributing
SchedTrip is a personal project, while it’s not yet open to external contributions, I welcome feedback and bug reports to improve it.

If you find a bug or want to submit a feature idea, feel free to open an [issue](https://github.com/AxelReviron/schedtrip/issues).

Contributions support will be opened once the project reaches a more stable version.

---

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
