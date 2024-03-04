
using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.EventSystems;

public abstract class CycleInteractionUIProgress : CycleInteractionUI
{

    protected override void ProcessingInput()
    {
        cycleAffected.SetProgress(NewProgress());
        cycleAffected.SetPositionAndRotationInCycle();
    }

    protected abstract float NewProgress();


}
