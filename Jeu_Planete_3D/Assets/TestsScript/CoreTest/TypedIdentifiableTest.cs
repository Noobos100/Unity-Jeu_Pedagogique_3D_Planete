/*
using System.Collections;
using System.Collections.Generic;
using NUnit.Framework;
using UnityEngine;
using UnityEngine.TestTools;


public class TypedIdentifiableTest
{
   private class TestTypedIdentifiable : TypedIdentifiable
    {
        private string identifier;

        public TestTypedIdentifiable(string id)
        {
            identifier = id;
        }

        public string GetIdentifier()
        {
            return identifier;
        }
    }

    [Test]
    public void TypedIdentifiable_GetIdentifier_ReturnsCorrectIdentifier()
    {
        // Arrange
        string expectedIdentifier = "TestIdentifier";
        TypedIdentifiable testObject = new TestTypedIdentifiable(expectedIdentifier);

        // Act
        string actualIdentifier = testObject.GetIdentifier();

        // Assert
        Assert.AreEqual(expectedIdentifier, actualIdentifier);
    }

    [Test]
    public void TypedIdentifiable_MatchRegex_ReturnsTrueForMatchingIdentifiers()
    {
        // Arrange
        string[] identifiers = { "ABC", "123" };
        string id = "TestABC123";
        
        // Act
        bool isMatching = TypedIdentifiable.MatchRegex(id, identifiers);

        // Assert
        Assert.IsTrue(isMatching);
    }

    [Test]
    public void TypedIdentifiable_MatchRegex_ReturnsFalseForNonMatchingIdentifiers()
    {
        // Arrange
        string[] identifiers = { "XYZ", "456" };
        string id = "TestABC123";
        
        // Act
        bool isMatching = TypedIdentifiable.MatchRegex(id, identifiers);

        // Assert
        Assert.IsFalse(isMatching);
    }
}
*/
