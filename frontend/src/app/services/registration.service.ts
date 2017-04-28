import { Injectable } from '@angular/core';
import { Http, Response, Headers, RequestOptions } from '@angular/http';
import { Registration } from './objects/registration';

import { Observable } from 'rxjs/Observable';

import 'rxjs/add/operator/catch';
import 'rxjs/add/operator/map';
import 'rxjs/add/observable/throw';

@Injectable()
export class RegistrationService {

  private regUrl: string = 'http://vapeboyz.xyz/api/user';

  constructor(private http: Http) { }

  register(user: Registration) {
    console.log('registering user');
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });
    console.log(user);
    console.log(JSON.stringify(user));

    return this.http.post(this.regUrl, user, options)
                    .map(this.extractData)
                    .catch(
                      (err) => {
                        return Promise.reject(err);
                      }
                    );
  }

  private extractData(res: Response) {
    if(!res.text()){
      console.log(res.statusText);
    }
  }

}
