# Sliding Puzzle 

Small web-based application for sliding puzzles.

## Development Commands

Use these commands until a more robust local environment has been built

Start Redis:
```bash
    redis-server
```

Serve Slim:
```bash
    php -S 0.0.0.0:8080 -t public index.php
```

Start React:
```bash
    npm run dev
```

## Todo:

- [ ] Use JWT so more than one puzzle can be played at once
- [ ] Create Tile component 
- [ ] Create home screen
- [ ] Add complete message
- [ ] Add timer
- [ ] Design and styles
- [ ] Add Unit Tests
- [ ] Dockerize and write commands for managing
- [ ] Add Score board
- [ ] Allow for size of puzzle to be selected
- [ ] Allow for users to use their own images
- [ ] Create cloud production env
- [ ] Add Travis CI pipeline

