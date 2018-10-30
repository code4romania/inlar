# International Network on Legal Assistance to Refugees (INLAR) 

[![GitHub contributors](https://img.shields.io/github/contributors/code4romania/inlar.svg?style=for-the-badge)](https://github.com/code4romania/inlar/graphs/contributors) [![GitHub last commit](https://img.shields.io/github/last-commit/code4romania/inlar.svg?style=for-the-badge)](https://github.com/code4romania/inlar/commits/master) [![License: MPL 2.0](https://img.shields.io/badge/license-MPL%202.0-brightgreen.svg?style=for-the-badge)](https://opensource.org/licenses/MPL-2.0)

International Network on Legal Assistance to Refugees (INLAR), is the winning project of the 2016 Young Professionals “Europe Lab” Forum. The web app, developed by Code for Romania, maps NGOs offering legal support for refugees, facilitating knowledge exchange between these entities as well as access to their services and to relevant information for refugees and asylum seekers.

[See the project live](http://inlar.org/en/)

[Built with](#built-with) | [Deployment](#deployment) | [Contributing](#contributing) | [Feedback](#feedback) | [License](#license) | [About Code4Ro](#about-code4ro)

## Built With

### Programming languages

- PHP
- JavaScript

### Platforms

- WordPress

### Package managers

- Gulp
- Bundler

### Database technology & provider

- MySQL

## Deployment

1. `bundle install`
2. Rename and configure the SAMPLE files under [config/deploy](config/deploy)
```
├── SAMPLE.deploy.rb
├── deploy
│   ├── SAMPLE.production.rb
│   └── SAMPLE.staging.rb
└── secrets
    ├── SAMPLE.db.yaml
    └── SAMPLE.salts.yaml
```

3. `cap staging deploy`

## Contributing

If you would like to contribute to one of our repositories, first identify the scale of what you would like to contribute. If it is small (grammar/spelling or a bug fix) feel free to start working on a fix. If you are submitting a feature or substantial code contribution, please discuss it with the team and ensure it follows the product roadmap.

* Fork it (https://github.com/code4romania/inlar/fork)
* Create your feature branch (git checkout -b feature/fooBar)
* Commit your changes (git commit -am 'Add some fooBar')
* Push to the branch (git push origin feature/fooBar)
* Create a new Pull Request

## Feedback

* Request a new feature on GitHub.
* Vote for popular feature requests.
* File a bug in GitHub Issues.
* Email us with other feedback contact@code4.ro

## License

This project is licensed under the MPL 2.0 License - see the [LICENSE](LICENSE) file for details

## About Code4Ro

Started in 2016, Code for Romania is a civic tech NGO, official member of the Code for All network. We have a community of over 500 volunteers (developers, ux/ui, communications, data scientists, graphic designers, devops, it security and more) who work pro-bono for developing digital solutions to solve social problems. #techforsocialgood. If you want to learn more details about our projects [visit our site](https://www.code4.ro/en/) or if you want to talk to one of our staff members, please e-mail us at contact@code4.ro.

Last, but not least, we rely on donations to ensure the infrastructure, logistics and management of our community that is widely spread accross 11 timezones, coding for social change to make Romania and the world a better place. If you want to support us, [you can do it here](https://code4.ro/en/donate/).
