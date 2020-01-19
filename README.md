# Sliding Puzzle 

Small web-based application for sliding puzzles.

The frontend application is a React JS SPA using Redux. The backend is written in PHP Slim framework. 

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

- [x] Use JWT so more than one puzzle can be played at once
- [x] Add timer
- [x] Add complete message
- [x] Add check if the puzzle is solvable after shuffle
- [ ] Design and styles
- [ ] Create home screen
- [ ] Create Tile component and move logic outside of component
- [x] Add Unit Tests
- [ ] Dockerize and write commands for managing
- [ ] Add Score board
- [ ] Allow for size of puzzle to be selected
- [ ] Allow for users to use their own images
- [ ] Create cloud production env
- [ ] Add Travis CI pipeline
- [ ] Simplify PuzzleEngine logic by using matrix of tile indexes
- [ ] Update Dependencies 

