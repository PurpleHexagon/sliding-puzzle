const chai = require('chai')
const { expect } = chai
import sinon from 'sinon'
import sinonChai from 'sinon-chai'
import { START_PUZZLE, startPuzzle } from '../puzzle'
import axios from 'axios'
chai.use(sinonChai);

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

    it('should reset the count', () => {
      expect(dispatch).to.have.been.calledWith(
        {
          type: START_PUZZLE,
          payload: []
        }
      )
    })
  })
})
