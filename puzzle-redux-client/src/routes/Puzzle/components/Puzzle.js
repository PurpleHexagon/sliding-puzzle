import React, { Component } from 'react';
import PropTypes from 'prop-types';
import logo from '../assets/logo.svg'
import './Puzzle.scss'
import { compose, curryN, forEach, isNil, multiply, partial, times, unapply } from 'ramda'

let performMoveFrom
function moveBlockHandler(moveBlockAction, event) {
  if (isNil(performMoveFrom)) {
    const performMove = curryN(2, unapply(function _performMove(fromTo) {
      moveBlockAction(fromTo)
    }))
    performMoveFrom = performMove(
      event.target.attributes.getNamedItem('data-puzzle-index').value
    )
  } else {
    const result = performMoveFrom(
      event.target.attributes.getNamedItem('data-puzzle-index').value
    )
    performMoveFrom = undefined
  }

}

function *puzzleBlock(moveBlockAction, x) {
    yield <div onClick={partial(moveBlockHandler, [moveBlockAction])}
               className="PuzzleBlock">
             <div data-puzzle-index={x} className="PuzzleBlockInner">{ x }</div>
          </div>;
    // x++
}

class Puzzle extends Component {
  render() {
    console.log(this.props.puzzleList)
    const puzzleBlocks = []
    // puzzleBlocks.push(
    //   compose(
    //     times(partial(puzzleBlock, [this.props.moveBlock])),
    //     multiply(this.props.dimension)
    //   )(this.props.dimension)
    // )

    forEach((x) => {
      puzzleBlocks.push(
        puzzleBlock(this.props.moveBlock, x)
      )
    })(this.props.puzzleList)

    return (
      <div className="App">
        <div className="App-header">
          <h2>The Panda Puzzle</h2>
        </div>
        <p className="App-intro">
          Click a square to move from, then click a square to move to
        </p>
        <div className="Puzzle">
          <div className="PuzzleContainer">
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
    moveBlock : PropTypes.func.isRequired,
}

Puzzle.defaultProps = {
    dimension: 3,
}

export default Puzzle
