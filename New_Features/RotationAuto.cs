using System;
using System.Collections;
using System.Collections.Generic;
using UnityEngine;

[RequireComponent(typeof(RotationCycle))]
public class RotationAuto : MonoBehaviour
{
    private RotationCycle rotationCycleScript;
    public bool autoRotate = true;
    
    // Start is called before the first frame update
    void Start()
    {
        rotationCycleScript = gameObject.GetComponent<RotationCycle>();
    }

    
    
    IEnumerator AutoRotation()
    {
        float rotatorSpeed = 1f / rotationCycleScript.rotatePeriod;
        while (autoRotate)
        {
            // Update progress
            rotationCycleScript.rotateProgress += Time.deltaTime * rotatorSpeed;
            rotationCycleScript.rotateProgress %= 1f;
            
            // Update Rotation
            rotationCycleScript.SetRotation();
            
            yield return null;
        }
        yield return null;
    }

    /*private void OnMouseDown()
    {
        //Debug.Log("Mouse Down");
        autoRotate = false;
        StopCoroutine(AutoRotation());
    }*/

    private void OnDisable()
    {
        autoRotate = false;
        StopCoroutine(AutoRotation());
    }

    /*private void OnMouseUp()
    {
        //Debug.Log("Mouse Up");
        autoRotate = true;
        StartCoroutine(AutoRotation());
    }*/

    private void OnEnable()
    {
        rotationCycleScript = gameObject.GetComponent<RotationCycle>();
        autoRotate = true;
        StartCoroutine(AutoRotation());
    }
}
