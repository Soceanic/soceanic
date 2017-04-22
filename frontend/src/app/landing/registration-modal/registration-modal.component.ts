import { Component, OnInit, OnDestroy } from '@angular/core';
import { RegistrationService } from 'app/services/registration.service';
import { Registration } from 'app/services/objects/registration';

import { SharedModule } from 'app/shared/shared.module';
import { ModalComponent } from 'app/shared/modal/modal.component';

@Component({
  selector: 'app-registration-modal',
  templateUrl: './registration-modal.component.html',
  styleUrls: ['./registration-modal.component.css']
})
export class RegistrationModalComponent implements OnInit, OnDestroy {

  private reg: Registration;
  registered: boolean;

  constructor(private service: RegistrationService) { }

  ngOnInit() {
    this.reg = new Registration();
  }

  ngOnDestroy() {
    this.reg = null;
  }

  register(){
    if(!this.reg) return "fuck";
    this.service.register(this.reg)
                .subscribe(
                  reg => console.log('registered successfully!'),
                  error => console.log('shits broke', error)
                );
  }



}
