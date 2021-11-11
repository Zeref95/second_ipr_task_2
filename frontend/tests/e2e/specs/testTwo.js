// https://docs.cypress.io/api/introduction/api.html
//import BookingTickets from '@/BookingTickets.vue'
import {getDatePlusDays} from "./helper";
let date = getDatePlusDays(10);

describe('My Movie app', () => {
  it('should not allow chose more when six places', () => {
    cy.visit('/')
    cy.contains('button', 'Tomorrow').click();
    cy.get('input[type="date"]').type(date).should('have.value', date)
    cy.get('.movie-list>.movie:first-child').click();
    cy.get('.move-info-center ul>li').last().click();
    for (let i=0; i<7; i++) {
      cy.get('.plates .cube-available').first().click();
    }
    cy.on('window:alert', (text) => {
      expect(text).to.contains('You cannot take more then 6 tickets');
    });
  })
})