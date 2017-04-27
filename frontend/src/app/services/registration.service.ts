import { Injectable } from '@angular/core';
import { Http, Response, Headers, RequestOptions } from '@angular/http';

import { Observable } from 'rxjs/Observable';

import 'rxjs/add/operator/catch';
import 'rxjs/add/operator/map';
import 'rxjs/add/observable/throw';

@Injectable()
export class RegistrationService {

  private regUrl: string = 'http://vapeboyz.xyz/api/user';

  constructor(private http: Http) { }

  register(user) {
    console.log('registering user');
    let headers = new Headers({ 'Content-Type': 'application/json' });
    let options = new RequestOptions({ headers: headers });

    return this.http.post(this.regUrl, user, options)
                    .map(this.extractData)
                    .catch((err: any) => {
                      console.log('fucking up at the post in reg service');
                      return Observable.throw(err);
                    }
                  );
  }

  private extractData(res: Response) {
    let body = res.json();
    console.log(body);
    return body || { };
  }

  private handleError (error: Response | any) {
    // In a real world app, you might use a remote logging infrastructure
    let errMsg: string;
    if (error instanceof Response) {
      const body = error.json() || '';
      const err = body.error || JSON.stringify(body);
      errMsg = `${error.status} - ${error.statusText || ''} ${err}`;
    } else {
      errMsg = error.message ? error.message : error.toString();
    }
    console.error('fuck', error.status);
    console.error('error in registration.service', errMsg);
    return Observable.throw(errMsg);
  }

}
