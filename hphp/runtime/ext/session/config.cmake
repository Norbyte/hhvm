HHVM_DEFINE_EXTENSION("session"
  SOURCES
    ext_session.cpp
  HEADERS
    ext_session.h
  SYSTEMLIB
    ext_session.php
  DEPENDS
    libFolly
)