// https://docs.cypress.io/api/introduction/api.html
//import BookingTickets from '@/BookingTickets.vue'
const city = Cypress.env('city');
const apiKey =  Cypress.env('api_key');
const backendURL =  Cypress.env('backend_url');

import {getDatePlusDays} from "./helper";
let date = getDatePlusDays(10);


describe('My Movie app', () => {
    it('should work correctly', () => {
        cy.request(getRequestObject(`movies?city_name=${city}`)).then(
            (response) => {
                expect(response.body).to.have.property('today')
                expect(response.body).to.have.property('tomorrow')
            }
        )

        cy.request(getRequestObject(`get-movies-by-date?city_name=${city}&date=${date}`)).then(
            (response) => {
                let firstMovie = response.body[0];
                expect(firstMovie).to.have.property('poster')

                cy.request(getRequestObject(`movie-session?movie_id=${firstMovie.id}&date=${date}&city_name=${city}`))
                    .then((response) => {
                        let firstSession = response.body[0];
                        expect(firstSession).to.have.property('time')

                        cy.request(getPostRequestObject('order', {
                            session_id: firstSession.id,
                            places: [1,2,3],
                            is_test: 1
                        }))
                            .then((response) => {
                                expect(response.body).to.have.property('message', "Order successfully created (test order)")
                            })

                        cy.request(getPostRequestObject('order', {
                            session_id: firstSession.id,
                            places: [1,2,3,4,5,6,7],
                            is_test: 1
                        }, false))
                            .then((response) => {
                                expect(response).to.have.property('status', 400)
                            })
                    })
            }
        )
    })
})

function getRequestObject(url) {
    return {
        method: 'GET',
        url: backendURL + `api/v1/` + url,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Authorization': apiKey
        }
    }
}

function getPostRequestObject(url, body, failOnStatusCode = true) {
    return {
        method: 'POST',
        url: backendURL + `api/v1/` + url,
        failOnStatusCode: failOnStatusCode,
        headers: {
            'Content-Type': 'application/json',
            'X-Authorization': apiKey
        },
        body: body
    }
}