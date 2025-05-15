/* DHTML-Bibliothek */
/*
#################################################################################
///////////////////////////////////Alter Code////////////////////////////////////

Der Komplette Abschnitt kann weg da jeder Browser DOM erkennt. Der code ist mind. 15 Jahre alt.


var DHTML = false, DOM = false, MSIE4 = false, NS4 = false, OP = false;

if (document.getElementById) {
  DHTML = true;
  DOM = true;
} else {
  if (document.all) {
    DHTML = true;
    MSIE4 = true;
  } else {
    if (document.layers) {
      DHTML = true;
      NS4 = true;
    }
  }
}
if (window.opera) {
  OP = true;
}

function getElement (Mode, Identifier, ElementNumber) {
  var Element, ElementList;
  if (DOM) {
    if (Mode.toLowerCase() == "id") {
      Element = document.getElementById(Identifier);
      if (!Element) {
        Element = false;
      }
      return Element;
    }
    if (Mode.toLowerCase() == "name") {
      ElementList = document.getElementsByName(Identifier);
      Element = ElementList[ElementNumber];
      if (!Element) {
        Element = false;
      }
      return Element;
    }
    if (Mode.toLowerCase() == "tagname") {
      ElementList = document.getElementsByTagName(Identifier);
      Element = ElementList[ElementNumber];
      if (!Element) {
        Element = false;
      }
      return Element;
    }
    return false;
  }
  if (MSIE4) {
    if (Mode.toLowerCase() == "id" || Mode.toLowerCase() == "name") {
      Element = document.all(Identifier);
      if (!Element) {
        Element = false;
      }
      return Element;
    }
    if (Mode.toLowerCase() == "tagname") {
      ElementList = document.all.tags(Identifier);
      Element = ElementList[ElementNumber];
      if (!Element) {
        Element = false;
      }
      return Element;
    }
    return false;
  }
  if (NS4) {
    if (Mode.toLowerCase() == "id" || Mode.toLowerCase() == "name") {
      Element = document[Identifier];
      if (!Element) {
        Element = document.anchors[Identifier];
      }
      if (!Element) {
        Element = false;
      }
      return Element;
    }
    if (Mode.toLowerCase() == "layerindex") {
      Element = document.layers[Identifier];
      if (!Element) {
        Element = false;
      }
      return Element;
    }
    return false;
  }
  return false;
}

function getAttribute (Mode, Identifier, ElementNumber, AttributeName) {
  var Attribute;
  var Element = getElement(Mode, Identifier, ElementNumber);
  if (!Element) {
    return false;
  }
  if (DOM || MSIE4) {
    Attribute = Element.getAttribute(AttributeName);
    return Attribute;
  }
  if (NS4) {
    Attribute = Element[AttributeName]
    if (!Attribute) {
       Attribute = false;
    }
    return Attribute;
  }
  return false;
}

function getContent (Mode, Identifier, ElementNumber) {
  var Content;
  var Element = getElement(Mode, Identifier, ElementNumber);
  if (!Element) {
    return false;
  }
  if (DOM && Element.firstChild) {
    if (Element.firstChild.nodeType == 3) {
      Content = Element.firstChild.nodeValue;
    } else {
      Content = "";
    }
    return Content;
  }
  if (MSIE4) {
    Content = Element.innerText;
    return Content;
  }
  return false;
}

function setContent (Mode, Identifier, ElementNumber, Text) {
  var Element = getElement(Mode, Identifier, ElementNumber);
  if (!Element) {
    return false;
  }
  if (DOM && Element.firstChild) {
    Element.firstChild.nodeValue = Text;
    return true;
  }
  if (MSIE4) {
    Element.innerText = Text;
    return true;
  }
  if (NS4) {
    Element.document.open();
    Element.document.write(Text);
    Element.document.close();
    return true;
  }
}

Luke Eckardt 09.01.2025
*/


///////////////////////////////////Neuer Code////////////////////////////////////

// Funktion zum Abrufen eines Elements basierend auf ID, Name oder Tag-Name.
function getElement(identifier, mode = "id", elementNumber = 0) {
  // Wenn der Modus "id" ist, wird das Element anhand seiner ID abgerufen.
  if (mode === "id") {
    return document.getElementById(identifier);
  } 
  // Wenn der Modus "name" ist, werden alle Elemente mit dem gegebenen Namen abgerufen 
  // und das Element mit dem angegebenen Index zurückgegeben.
  else if (mode === "name") {
    const elements = document.getElementsByName(identifier);
    return elements[elementNumber] || null; // Gibt null zurück, wenn das Element nicht existiert.
  } 
  // Wenn der Modus "tagname" ist, werden alle Elemente mit dem gegebenen Tag-Namen abgerufen
  // und das Element mit dem angegebenen Index zurückgegeben.
  else if (mode === "tagname") {
    const elements = document.getElementsByTagName(identifier);
    return elements[elementNumber] || null; // Gibt null zurück, wenn das Element nicht existiert.
  }
  // Wenn der Modus ungültig ist, wird null zurückgegeben.
  return null;
}

// Funktion zum Abrufen eines Attributs eines Elements.
function getAttribute(identifier, attributeName, mode = "id", elementNumber = 0) {
  // Ruft zuerst das Element mit der getElement-Funktion ab.
  const element = getElement(identifier, mode, elementNumber);
  // Gibt den Attributwert zurück, falls das Element existiert, sonst null.
  return element ? element.getAttribute(attributeName) : null;
}

// Funktion zum Abrufen des Textinhalts eines Elements.
function getContent(identifier, mode = "id", elementNumber = 0) {
  // Ruft zuerst das Element mit der getElement-Funktion ab.
  const element = getElement(identifier, mode, elementNumber);
  // Gibt den Textinhalt zurück, falls das Element existiert, sonst einen leeren String.
  return element ? element.textContent : "";
}

// Funktion zum Setzen des Textinhalts eines Elements.
function setContent(identifier, text, mode = "id", elementNumber = 0) {
  // Ruft zuerst das Element mit der getElement-Funktion ab.
  const element = getElement(identifier, mode, elementNumber);
  // Setzt den Textinhalt, falls das Element existiert.
  if (element) {
    element.textContent = text;
    return true; // Gibt true zurück, wenn der Textinhalt erfolgreich gesetzt wurde.
  }
  return false; // Gibt false zurück, wenn das Element nicht existiert.
}