const chai = require('chai')
const { expect } = chai
import puzzleReducer, {MOVE, MOVE_COUNT, RESET_MOVE_COUNT, START_PUZZLE} from '../puzzle'

describe('reducers', () => {
  describe('RESET_MOVE_COUNT', () => {
    let newState

    beforeEach(() => {
      newState = puzzleReducer(
        { moveCount: 2 }, { type: RESET_MOVE_COUNT, payload: {} })
      return newState
    })

    it('should reset the count', () => {
      expect(newState).to.be.eql(
        { moveCount: 0 }
      )
    })
  })

  describe('MOVE_COUNT', () => {
    let newState

    beforeEach(() => {
      newState = puzzleReducer({ moveCount: 2 }, { type: MOVE_COUNT, payload: {} })
      return newState
    })

    it('should increment the count', () => {
      expect(newState).to.be.eql(
        { moveCount: 3 }
      )
    })
  })

  describe('START_PUZZLE', () => {
    let newState

    beforeEach(() => {
      newState = puzzleReducer({ tiles: [] }, { type: START_PUZZLE, payload: { tiles: [1, 2, 3] } })
      return newState
    })

    it('should set the tiles', () => {
      expect(newState).to.be.eql(
        { tiles: [1, 2, 3] }
      )
    })
  })

  describe('MOVE', () => {
    let newState

    beforeEach(() => {
      newState = puzzleReducer({ tiles: [1, 2, 3, 4] }, { type: MOVE, payload: { tiles: [1, 2, 4, 3] } })
      return newState
    })

    it('should replace the tiles', () => {
      expect(newState).to.be.eql(
        { tiles: [1, 2, 4, 3] }
      )
    })
  })
})
