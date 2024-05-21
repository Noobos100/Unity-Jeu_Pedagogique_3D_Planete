/*
using System.Collections;
using System.Collections.Generic;
using NUnit.Framework;
using UnityEngine;
using UnityEngine.TestTools;



public class IdentifiableRestrictableTests
{
    public class TestIdentifiableRestrictable : IdentifiableRestrictable
{
    public string objectType { get; set; }
    public bool activationRestricted { get; set; }
    public bool activationState { get; private set; }

    public void Activate(bool activation)
    {
        if (!activationRestricted)
            activationState = activation;
    }

    public bool GetDefaultActivation()
    {
        return activationRestricted;
    }

    // Implémentation de la méthode GetIdentifier() de l'interface TypedIdentifiable
    public string GetIdentifier()
    {
        // Vous pouvez renvoyer un identifiant par défaut ou définir une logique pour générer un identifiant
        return "DefaultIdentifier";
    }
}
[Test]
    public void Activate_ActivationNotRestricted_ActivatesCorrectly()
    {
        // Arrange
        var identifiableRestrictable = new TestIdentifiableRestrictable();
        identifiableRestrictable.activationRestricted = false;

        // Act
        identifiableRestrictable.Activate(true);

        // Assert
        Assert.IsTrue(identifiableRestrictable.activationState);
    }

    [Test]
    public void Activate_ActivationRestricted_NoActivationOccurs()
    {
        // Arrange
        var identifiableRestrictable = new TestIdentifiableRestrictable();
        identifiableRestrictable.activationRestricted = true;

        // Act
        identifiableRestrictable.Activate(true);

        // Assert
        Assert.IsFalse(identifiableRestrictable.activationState);
    }
    [Test]
    public void GetDefaultActivation_ReturnsCorrectValue()
    {
        // Arrange
        var identifiableRestrictable = new TestIdentifiableRestrictable();
        identifiableRestrictable.activationRestricted = true;

        // Act
        var defaultActivation = identifiableRestrictable.GetDefaultActivation();

        // Assert
        Assert.IsTrue(defaultActivation);
    }
     [Test]
    public void GetObjectType_ReturnsCorrectObjectType()
    {
        // Arrange
        var identifiableRestrictable = new TestIdentifiableRestrictable();
        identifiableRestrictable.objectType = "TypeA";

        // Act
        var objectType = identifiableRestrictable.objectType;

        // Assert
        Assert.AreEqual("TypeA", objectType);
    }
}
*/

