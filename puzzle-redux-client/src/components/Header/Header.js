import React from 'react'
import { IndexLink, Link } from 'react-router'
import './Header.scss'

export const Header = () => (
  <div className='App-header'>
    <h1>Sliding Puzzle</h1>
    <IndexLink to='/' activeClassName='route--active'>
      Home
    </IndexLink>
    {' · '}
    <Link to='/puzzle' activeClassName='route--active'>
      Puzzle
    </Link>
  </div>
)

export default Header
