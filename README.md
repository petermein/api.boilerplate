# api.boilerplate

An implementation of a lumen API with best design practices and learnings. (DDD, CQRS, ES, Rest and GraphQL)

## TODO

#### Must have

- [x] Upgrade to stable 7.4
- [x] Example routes for rest
- [x] Setup system for DTO objects
- [x] Symfony message bus for CQRS
- [x] Setup GraphQL for resource
- [x] Setup phpstan validations for ddd structure
- [ ] Integrate passport / alternative header parsing
- [ ] Write extensive documentation
- [x] Port to lumen

#### Should have

- [ ] Doctrine vs Laravel migrations
- [ ] Create deployment example
- [x] Add docker development support
- [ ] Add helm chart
- [ ] Create command generators
- [ ] WebUI example
- [ ] Rename base command (Boilerplate -> x)
- [ ] Typescript client generation pipeline
- [ ] Setup OpenApi parsing method
- [ ] Json schema or meta data provider for REST response

#### Could have

- [ ] Cloudevents / NATS
- [ ] Setup ES example
- [ ] Integrate twofactor with passport
- [ ] Write performance benchmark test
- [ ] Evaluate provider and defer structure
- [ ] Clean up unused laravel assets and dependencies (i.e. resources, routes)
- [ ] Create development and versioning for future use
