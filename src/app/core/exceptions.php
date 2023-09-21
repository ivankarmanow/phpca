<?php

namespace exceptions;

use Exception;

class MethodNotAllowed extends Exception {}

class RouterYetIncluded extends Exception {}

class IncludeParentRouter extends Exception {}

class DispatcherHasNotParents extends Exception {}

class FactoryAlreadyExists extends Exception {}

class DependencyNotFound extends Exception {}

class ValueError extends Exception {}

