import React, { Component } from 'react'
import PropTypes from 'prop-types'
import './Puzzle.scss'
import { curryN, equals, findIndex, forEachObjIndexed, isNil, partial, unapply } from 'ramda'

let performMoveFrom
function moveBlockHandler (moveBlockAction, puzzleList, event) {
  if (isNil(performMoveFrom)) {
    const performMove = curryN(2, unapply(function _performMove (fromTo) {
      moveBlockAction(fromTo)
    }))
    performMoveFrom = performMove(
      parseInt(event.currentTarget.attributes.getNamedItem('data-outer-puzzle-index').value, 10) + 1
    )
    const emptyTileIndex = findIndex(equals(9))(puzzleList)
    performMoveFrom(parseInt(emptyTileIndex, 10) + 1)
    performMoveFrom = undefined
  } else {
    performMoveFrom(
      parseInt(event.currentTarget.attributes.getNamedItem('data-outer-puzzle-index').value, 10) + 1
    )
    performMoveFrom = undefined
  }
}

function *puzzleBlock (moveBlockAction, puzzleList, x, key) {
  yield <div onClick={partial(moveBlockHandler, [moveBlockAction, puzzleList])}
    key={key}
    data-outer-puzzle-index={key}
    className='PuzzleBlock'>
    <div data-puzzle-index={x} className='PuzzleBlockInner' />
  </div>
}

class Puzzle extends Component {
  constructor (props) {
    super(props)

    this.state = {
      runningTime: 0
    }
  }
  componentDidMount () {
    this.props.resetMoveCount()
    return this.props.startPuzzle()
    .then(() => {
      this.timer = setInterval(this.updateTimer.bind(this), 500)
    })
  }
  updateTimer () {
    const startedDate = new Date(this.props.started)
    this.setState({ runningTime: parseInt(Math.abs(new Date() - startedDate) / 1000, 10) })
  }
  render () {
    if (this.props.isSolved === true) {
      clearInterval(this.timer)
    }

    const puzzleBlocks = []
    forEachObjIndexed((x, key) => {
      puzzleBlocks.push(
        puzzleBlock(this.props.moveBlock, this.props.puzzleList, x, key)
      )
    })(this.props.puzzleList)

    return (
      <div>
        <div>
          <h2>The Panda Puzzle</h2>
        </div>
        <p className='App-intro'>
          Click a square adjacent to the empty square to move it.
        </p>
        <div>
          Moves: { this.props.moveCount }
        </div>
        <div>
          Status: { this.props.isSolved ? 'Complete' : 'Incomplete' }
        </div>
        <div>
          Running Time: { this.state.runningTime } Seconds
        </div>
        <div className={'Puzzle ' + (this.props.isSolved ? 'Complete' : 'Incomplete')}>
          <div className='PuzzleContainer'>
            { puzzleBlocks }
          </div>
        </div>
      </div>
    )
  }
}

Puzzle.propTypes = {
  dimension: PropTypes.number,
  puzzleList: PropTypes.array,
  isSolved: PropTypes.bool,
  started: PropTypes.string,
  moveCount: PropTypes.number,
  moveBlock : PropTypes.func.isRequired,
  startPuzzle : PropTypes.func.isRequired,
  resetMoveCount : PropTypes.func.isRequired
}

Puzzle.defaultProps = {
  dimension: 3
}

export default Puzzle
