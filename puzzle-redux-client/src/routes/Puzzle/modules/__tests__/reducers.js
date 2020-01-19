const chai = require('chai')
const { expect } = chai
import puzzleReducer, { MOVE_COUNT, RESET_MOVE_COUNT } from '../puzzle'

describe('reducers', () => {
  describe('RESET_MOVE_COUNT', () => {
    let newState

    beforeEach(() => {
      newState = puzzleReducer(
        { moveCount: 2 }, { type: RESET_MOVE_COUNT, payload: {} })
      console.log(newState)
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
})
