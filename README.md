# Entity Collection

## Definitions

An Entity Collection is a configuration entity that is used to group a collection of different types of entities.

An entity collection consists of 4 things:

1. **List Style**: The data structure form of the collection's content. Examples:

  * Flat list
     * Queue
     * Stack
  * Tree
     * Unlimited levels
     * Limited levels
  * ... ?

2. **Row Display**: Settings and options about the collection's items rendering. Examples:

  * View modes: All the entities that are contained in the list will be rendered on a view mode. This could be:
     * Standard: All entities of each type are rendered on the same view mode.
     * Alternating: Different view mode for odd or even.
     * Per item: Each item in the collection is rendered on a different view mode that the admin selects.
  * Class options: Add a list of classes to the entity's wrapper element.

3. **Storage Setting**: Plugin for the collection's storage. The default is "DB storage". Other examples:
  * JSON export
  * MongoDB server

4. **Entity collection content**: The actual content of the entity collection, the group of entities that belong to
the collection with their position in it.




[![Gitter](https://badges.gitter.im/entity_collection/Lobby.svg)](https://gitter.im/entity_collection/Lobby?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)