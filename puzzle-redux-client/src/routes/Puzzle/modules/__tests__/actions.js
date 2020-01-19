const chai = require('chai')
const { expect } = chai
import sinon from 'sinon'
import sinonChai from 'sinon-chai'
import {moveBlock, MOVE, resetMoveCount, RESET_MOVE_COUNT, startPuzzle, START_PUZZLE, MOVE_COUNT} from '../puzzle'
import axios from 'axios'
chai.use(sinonChai)

describe('actions', () => {
  let sandbox
  beforeEach(() => { sandbox = sinon.sandbox.create() })
  afterEach(() => sandbox.restore())

  describe('startPuzzle', () => {
    let newState
    let dispatch

    beforeEach(() => {
      dispatch = sinon.spy()
      const resolved = new Promise((r) => r({ data: [] }))
      sandbox.stub(axios, 'get').returns(resolved)

      newState = startPuzzle()(dispatch)
      return newState
    })

    it('should call get', () => {
      expect(axios.get).to.have.been.calledWith('http://0.0.0.0:8080/start-puzzle')
    })

    it('should call dispatch', () => {
      expect(dispatch).to.have.been.calledWith(
        {
          type: START_PUZZLE,
          payload: []
        }
      )
    })
  })

  describe('resetMoveCount', () => {
    let newState
    let dispatch

    beforeEach(() => {
      dispatch = sinon.spy()

      newState = resetMoveCount()(dispatch)
      return newState
    })

    it('should call dispatch', () => {
      expect(dispatch).to.have.been.calledWith(
        {
          type: RESET_MOVE_COUNT,
          payload: {}
        }
      )
    })
  })

  describe('moveBlock', () => {
    let newState
    let dispatch

    beforeEach(() => {
      dispatch = sinon.spy()
      const resolved = new Promise((r) => r({ data: [] }))
      sandbox.stub(axios, 'post').returns(resolved)

      const getState = () => ({ puzzle: { token: '1234' } })

      newState = moveBlock()(dispatch, getState)
      return newState
    })

    it('should call get', () => {
      expect(axios.post).to.have.been.calledWith('http://0.0.0.0:8080/move')
    })

    it('should call dispatch', () => {
      expect(dispatch).to.have.been.calledWith(
        {
          type: MOVE,
          payload: []
        }
      )

      expect(dispatch).to.have.been.calledWith(
        {
          type: MOVE_COUNT,
          payload: {}
        }
      )
    })
  })
})
