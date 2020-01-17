const chai = require('chai')
const { expect } = chai
import puzzleReducer, { RESET_MOVE_COUNT } from '../puzzle'

describe('reducers', () => {
  before(() => {
    return puzzleReducer({ moveCount: 2 })
  })

  describe('RESET_MOVE_COUNT', () => {
    let newState

    beforeEach(() => {
      newState = puzzleReducer({ moveCount: 0 }, RESET_MOVE_COUNT)
      return newState
    })

    it('should reset the count', () => {
      expect(newState).to.be.eql(
        { moveCount: 0 }
      )
    })
  })
})
