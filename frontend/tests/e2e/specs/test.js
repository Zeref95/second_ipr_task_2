// https://docs.cypress.io/api/introduction/api.html
//import BookingTickets from '@/BookingTickets.vue'
import {getDatePlusDays} from "./helper";
let date = getDatePlusDays(10);
describe('My Movie app', () => {
  it('should open main page', () => {
    cy.visit('/')
    cy.contains('button', 'Today')
    cy.contains('button', 'Tomorrow')
    cy.contains('b', 'Or chose a day:')
    cy.get('input[type="date"]').should('have.value', '')
  })
  it('should show movies at today and tomorrow', () =>  {
    cy.get('.movie-list>.movie');
    cy.contains('button', 'Today').click();
    cy.get('.movie-list>.move>h3,img')
    cy.contains('button', 'Tomorrow').click();
    cy.get('.movie-list>.move>h3,img')
  })
  it('should show movies when change date', () =>  {
      cy.get('input[type="date"]').type(date).should('have.value', date)
      cy.get('.movie-list>.move>h3,img')
  })
  it('should open movie info', () => {
    cy.get('.movie-list>.movie:first-child').click();
    cy.contains('button', 'Go back');
    cy.get('.move-info-left>img')
    cy.get('.move-info-left>p')
    cy.get('.move-info-center ul>li')
    cy.contains('.flex-center', 'Available')
  })
  it('should open place list', () => {
    cy.get('.move-info-center ul>li').last().click();
    cy.contains('.screen', 'Screen')
    cy.get('.cube-available')
  })
  it('should chose seats', () => {
    cy.get('.plates .cube-available').first().click();
    cy.get('.plates  .cube-your-chose')
  })
  it('should make order', () => {
    cy.get('.plates .cube-available').first().click();
    cy.get('.plates .cube-available').first().click();
    cy.contains('button', 'Order').click();
    cy.contains('h2', 'Thanks for your order');
  })
})