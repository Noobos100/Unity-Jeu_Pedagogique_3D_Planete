/*
using System.Collections;
using System.Collections.Generic;
using NUnit.Framework;
using UnityEngine;
using UnityEngine.TestTools;



public class ActivationRestrictableTests
{
    // Implémentation de l'interface ActivationRestrictable pour les tests
    public class TestActivationRestrictable : ActivationRestrictable
    {
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
    }

    [Test]
    public void Activate_ActivationNotRestricted_ActivatesCorrectly()
    {
        // Arrange
        var restrictable = new TestActivationRestrictable();
        restrictable.activationRestricted = false;

        // Act
        restrictable.Activate(true);

        // Assert
        Assert.IsTrue(restrictable.activationState);
    }

    [Test]
    public void Activate_ActivationRestricted_NoActivationOccurs()
    {
        // Arrange
        var restrictable = new TestActivationRestrictable();
        restrictable.activationRestricted = true;

        // Act
        restrictable.Activate(true);

        // Assert
        Assert.IsFalse(restrictable.activationState);
    }

    [Test]
    public void GetDefaultActivation_ReturnsCorrectValue()
    {
        // Arrange
        var restrictable = new TestActivationRestrictable();
        restrictable.activationRestricted = true;

        // Act
        var defaultActivation = restrictable.GetDefaultActivation();

        // Assert
        Assert.IsTrue(defaultActivation);
    }
}
*/

